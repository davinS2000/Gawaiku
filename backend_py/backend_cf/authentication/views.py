from rest_framework import status
from rest_framework import generics
from rest_framework import viewsets
from rest_framework import filters
from rest_framework.response import Response
from rest_framework.settings import api_settings
from rest_framework_simplejwt.serializers import TokenObtainPairSerializer
from rest_framework_simplejwt.tokens import AccessToken, RefreshToken
from rest_framework_simplejwt import authentication
from rest_framework_simplejwt.views import TokenObtainPairView
from rest_framework.authtoken.views import ObtainAuthToken
from rest_framework.authentication import TokenAuthentication
from rest_framework import permissions
from rest_framework.permissions import IsAuthenticatedOrReadOnly, IsAuthenticated
from rest_framework.views import APIView

from django.contrib.sites.shortcuts import get_current_site
from django.core.mail import EmailMessage
import threading
from django.conf import settings
from django.contrib.auth import authenticate
from django.contrib.auth import login
from django.http import HttpResponseRedirect

from authentication.models import UserProfile
# from authentication.serializer import UserSerializer, VerificationSerializer
from authentication.serializer import VerificationSerializer, UserProfileSerializer
from authentication import utils
from authentication import models
from authentication import serializer
from authentication import permission

from api import models as models_api

# generics used for createAPIView that already create standard operation

# class CreateUserView(generics.CreateAPIView):

#     """Create a new user in the  system"""
#     serializer_class = UserSerializer


class UserProfileViewSet(viewsets.ModelViewSet):

    """Handle creating and updating profiles"""
    serializer_class = UserProfileSerializer
    queryset = models.UserProfile.objects.all()
    authentication_classes = (TokenAuthentication,)
    permission_classes = (permission.UpdateOwnProfile,)


class RegisterAPIView(generics.GenericAPIView):
    """Register API View using JWT token"""
    serializer_class = serializer.RegisterSerializer

    def post(self, request):
        serializer = self.get_serializer(data=request.data)
        if (serializer.is_valid()):
            user = serializer.save()
            token_request = TokenObtainPairSerializer().get_token(request.user)
            token_access = AccessToken().for_user(request.user)
            token_request['user_id'] = str(user.id)
            token_access['user_id'] = str(user.id)

            serializer = VerificationSerializer(data=request.data)
            if (serializer.is_valid()):
                user_data = serializer.data
                user = UserProfile.objects.get(email=user_data['email'])
                token = RefreshToken.for_user(user).access_token
                url = get_current_site(request).domain
                relativeLink = '/api/auth/verify-email/confirm/'
                absurl = 'http://' + url + \
                    relativeLink + "?token=" + str(token)
                email_body = 'Hi ' + user.email + \
                    ' Use the link below to verify your email \n' + absurl
                data = {'email_body': email_body, 'to_email': user.email,
                        'email_subject': 'Verify your email'}
                email = EmailMessage(subject=data['email_subject'],
                                     body=data['email_body'],
                                     to=[data['to_email']])
                # EmailThread(email).start()
            return Response({
                "status": 201, "message": "successful register",
                "refresh_token": str(token_request), "access_token": str(token_access)
            }, status=status.HTTP_201_CREATED)

        name_errors = []
        email_errors = []
        password_errors = []

        if serializer.data['name'].strip() == "":
            name_errors.append("Please fill the name")

        if not utils.check_email(serializer.data['email']):
            email_errors.append('Email not valid')

        if UserProfile.objects.filter(email=serializer.data['email']).exists():
            email_errors.append('Email already used for register')

        if len(serializer.data['password']) <= 8:
            password_errors.append('Password must be > 8 characters')

        return Response({
            'status': 401, "name": name_errors,
            "email": email_errors,
            "password": password_errors,
        }, status=status.HTTP_401_UNAUTHORIZED)


class LoginAPIView(TokenObtainPairView):
    """Login API using JWT token"""
    serializer_class = serializer.CustomTokenObtainPairSerializer
    # authentication_class = ()
    # permission_classes = (permission.AllowAny,)

    # def login(request):
    #     if request.method == 'POST':
    #         username = request.POST['username']
    #         password = request.POST['password']
    #         user = authenticate(request, username=username, password=password)
    #         if user is not None:
    #             login(request, user)
    #             user_id = request.user.id
    #             print(user_id)
    #             return Response(f"Login successful. User ID: {user_id}")
    #             # return HttpResponseRedirect(f"http://localhost/1__Skripsi/Web_CF/main.php?user_id={user_id}")
    #             # return HttpResponseRedirect(f"http://localhost/1__Skripsi/Web_CF/backend_py/backend_cf/api/test_get_id_login.php?user_id={user_id}")
    #         else:
    #             return Response("Login failed. Invalid username or password.")


class UserView(APIView):
    authentication_class = (TokenAuthentication,)
    permission_classes = (permissions.IsAuthenticated,)

    def get(self, request):
        # return the user's id
        return Response({'user_id': request.user.id})


class UserProfileFeedViewSet(viewsets.ModelViewSet):
    """Handles creating, reading, and updating profile feed items"""
    authentication_classes = (TokenAuthentication,)
    serializer_class = serializer.ProfileFeedItemSerializer
    queryset = models.ProfileFeedItem.objects.all()
    permission_classes = (
        permission.UpdateOwnStatus,
        IsAuthenticatedOrReadOnly
    )

    def perform_create(self, serializer):
        """Sets the user profile to the logged in user"""
        serializer.save(user_profile=self.request.user)


class AuthenticatedAPIView(generics.GenericAPIView):
    """Authenticated API View"""
    authentication_classes = (authentication.JWTAuthentication,)
    permission_classes = (permissions.IsAuthenticated,)

    def get(self, request):
        """GET Request for authenticated user"""
        return Response({'message': 'Successfully login'})


# class InitUserView(generics.GenericAPIView):
#     """Init user Data view"""

#     def get(self, request):
#         ratings_count = models_api.Rating.objects.all().count()
#         users_count = UserProfile.objects.all().count()

#         if users_count < ratings_count:
#             needed_user_count = ratings_count - users_count

#             for id in range(needed_user_count):
#                 user = UserProfile(
#                     email=f"test{id}@mail.com", name=f"robot{id}")
#                 user.save()

#             return Response({'message': needed_user_count})
#         return Response({'message': "already supplied"})
