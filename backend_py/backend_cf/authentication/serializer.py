from rest_framework import serializers
from django.contrib.auth import get_user_model
from rest_framework_simplejwt.serializers import TokenObtainPairSerializer
from django.contrib.auth import authenticate
from rest_framework_simplejwt.settings import api_settings
from rest_framework.exceptions import PermissionDenied
from rest_framework import status

from authentication import models
from authentication.models import UserProfile
from authentication import utils


class CustomException(PermissionDenied):
    """Custom Exception that used on CustomTokenObtainPairSerializer"""
    status_code = status.HTTP_401_UNAUTHORIZED

    def __init__(self, detail, status_code=None):
        self.detail = detail
        if status_code is not None:
            self.status_code = status_code


# class UserSerializer(serializers.ModelSerializer):

#     """Serializer for the users object"""


#     class Meta:

#         model = get_user_model()

#         # fields where it will be accessible on api
#         fields = ('id','email', 'password', 'name')

#         # extra_kwargs for configured extra settings in model serializer
#         # to ensure the password is write only and minimum 8 characters
#         extra_kwargs = {'password': {'write_only': True, 'min_length': 8}}


#     def create(self, validated_data):
#         """Create a new user with encrypted password and return it"""
#         return get_user_model().objects.create_user(**validated_data)


class UserProfileSerializer(serializers.ModelSerializer):

    """Serializers a user profile object"""
    class Meta:

        model = get_user_model()

        # fields where it will be accessible on api
        fields = ('id', 'email', 'password', 'name')

        # extra_kwargs for configured extra settings in model serializer
        # to ensure the password is write only and minimum 8 characters
        extra_kwargs = {'password': {'write_only': True, 'min_length': 8}}

        def create(self, validated_data):
            """Create a new user with encrypted password and return it"""
            return get_user_model().objects.create_user(**validated_data)

        def update(self, instance, validated_data):
            if 'password' in validated_data:
                password = validated_data.pop('password')
                instance.set_password(password)
            return super().update(instance, validated_data)


class ProfileFeedItemSerializer(serializers.ModelSerializer):
    """Serializer profile Feed Items"""
    class Meta:
        model = models.ProfileFeedItem
        fields = ('id', 'user_profile', 'status_text', 'created_on')
        extra_kwargs = {'user_profile': {'read_only': True}}


class RegisterSerializer(serializers.ModelSerializer):
    """Serializer for Register api"""

    email = serializers.EmailField(max_length=50, min_length=6)
    password = serializers.CharField(min_length=8, write_only=True)

    class Meta:
        model = get_user_model()
        fields = ('name', 'email', 'password',)

    def validate(self, args):
        email = args.get('email', None)
        if UserProfile.objects.filter(email=email).exists():
            raise serializers.ValidationError({
                'email': ('email already exists')})
        return super().validate(args)

    def create(self, validated_data):
        return get_user_model().objects.create_user(**validated_data)


class CustomTokenObtainPairSerializer(TokenObtainPairSerializer):
    """Custom token obtain pair serializer"""

    def validate(self, attrs):
        authenticate_kwargs = {
            self.username_field: attrs[self.username_field],
            'password': attrs['password'],
        }
        try:
            authenticate_kwargs['request'] = self.context['request']
        except KeyError:
            pass

        self.user = authenticate(**authenticate_kwargs)

        if not api_settings.USER_AUTHENTICATION_RULE(self.user):
            email_errors = []
            password_errors = []

            if not utils.check_email(attrs['email']):
                email_errors.append("Email not valid")
            if len(attrs['password']) <= 8:
                password_errors.append("Password must be > 8 characters")

            raise CustomException(detail={'status': 401,
                                          "email": email_errors,
                                          "password": password_errors},
                                  status_code=status.HTTP_401_UNAUTHORIZED
                                  )

        data = {}
        refresh = self.get_token(self.user)
        data['refresh_token'] = str(refresh)
        data['access_token'] = str(refresh.access_token)
        data['user_id'] = self.user.id
        data['status'] = 201
        data['message'] = "Successful Login"
        return data


class VerificationSerializer(serializers.Serializer):
    """Serializer for Register api"""

    email = serializers.EmailField(max_length=50, min_length=6)

    def validate(self, args):
        email = args.get('email', None)
        if not UserProfile.objects.filter(email=email).exists():
            raise serializers.ValidationError({
                'email': ('email not available')})
        return super().validate(args)


class ConfirmVerificationSerializer(serializers.Serializer):
    token = serializers.CharField(max_length=555)
