from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from rest_framework import mixins
# from rest_framework.mixins import PermissionRequiredMixin
from rest_framework.generics import UpdateAPIView
from rest_framework.decorators import api_view
from django.http import HttpResponse, JsonResponse
import json
# from django.db import model
from django.contrib.auth.mixins import PermissionRequiredMixin
from django.shortcuts import get_object_or_404

# from api.serializers import HelloSerializer, SmartphoneSerializer, RatingSerializer, RatingPostSerializer
from api.serializers import HelloSerializer, SmartphoneSerializer, RatingSerializer, RatingDataSerializer
from api.models import Smartphone, Rating
from api.user_cf import run_analyze

import pandas as pd



class HelloApiView(APIView):
    """Test API View"""
    serializer_class = HelloSerializer

    # def get(self, request, format=None):
    #     """Returns a list of APIView features"""
    #     an_apiview = [
    #         'Uses HTTP methods as function (get, post, patch, put, delete)',
    #         'Is similar to a traditional Django View',
    #         'Gives you the most control over your application logic',
    #         'Is mapped manually to URLs',
    #     ]

    #     return Response({'message': 'Hello!', 'an_apiview': an_apiview})

    # def post(self, request):
    #     """Create a hello message with our name"""
    #     serializer = self.serializer_class(data=request.data)

    #     if serializer.is_valid():
    #         user_id = serializer.validated_data.get('user_id')

    # sim_dict, data_brand_score = run_analyze(
    #     user_id)
    # sim_dict[user_id].pop?(0)
    # return Response({'Similarity User': sim_dict, 'score brand': data_brand_score})
    #     else:
    # return Response(
    #     serializer.errors,
    #     status=status.HTTP_400_BAD_REQUEST
    # )

    # def get(self, request):

    #     value = request.GET.get('value')
    #     return HttpResponse("Value Accepted: $value")


class SmartphoneApiView(APIView):
    def get(self, request):
        smartphones = Smartphone.objects.all()
        serializer = SmartphoneSerializer(smartphones, many=True)
        return Response(serializer.data, status=status.HTTP_200_OK)

    def post(self, request):
        serializer = SmartphoneSerializer(data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


class RatingApiView(APIView):

    def get(self, request):
        ratings = Rating.objects.all()
        # if 'user_id' in list(request.GET):
        # user_id = request.GET['user_id']
        # current_id = user_id.object.filter(user_id=request.GET.get('user_id'))
        # data = Rating.objects.filter(user_id=user_id).values()
        serializer = RatingSerializer(ratings, many=True)
        return Response(serializer.data, status=status.HTTP_200_OK)
        # else:
        #     return Response({"message": "There is no user_id parameter"},
        #                     status=status.HTTP_400_BAD_REQUEST)

    def post(self, request):
        serializer = RatingSerializer(data=request.data)
        # submission = submission.first(
        if serializer.is_valid():

            submission_exists = Rating.objects.filter(
                user_id=serializer.validated_data['user_id']).exists()
            print(submission_exists)
            if submission_exists:
                print('error because u already submitted')
                return Response({'error': 'You have already submitted a rating'}, status=status.HTTP_400_BAD_REQUEST)

            # if request.method == 'GET':
            #     ratings = Rating.object.all()
            #     serializer = RatingSerializer(ratings, many=True)
            #     return Response(serializer.data)

            if serializer.is_valid():
                serializer.save()
                return Response(serializer.data, status=status.HTTP_201_CREATED)
            return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

# update rating by id


class RatingViewset(UpdateAPIView, PermissionRequiredMixin):
    serializer_class = RatingSerializer
    queryset = Rating.objects.all()
    lookup_field = 'pk'

# view get rating by id


class RatingDataView(APIView):
    serializer_class = RatingDataSerializer
    # queryset = Rating.objects.all()
    # lookup_field = 'pk'

    def get(self, request, pk):
        # serializer = self.serializer_class(data=request.data)
        model_instance = get_object_or_404(Rating, pk=pk)
        serializer = RatingDataSerializer(model_instance)
        # if serializer.is_valid():
        #     user_id = serializer.validated_data['user_id']

        return Response(serializer.data, status=status.HTTP_200_OK)


class user_cfView(APIView):
    serializer_class = HelloSerializer

    def post(self, request):
        serializer = self.serializer_class(data=request.data)

        if serializer.is_valid():
            input_user_id = serializer.validated_data['user_id']
            smartphones = Smartphone.objects.all()
            ratings = Rating.objects.all()

            print(smartphones)
            print(ratings)

            smartphone_json = json.loads(JsonResponse(
                list(Smartphone.objects.values()), safe=False).content.decode())
            rating_json = json.loads(JsonResponse(
                list(Rating.objects.values()), safe=False).content.decode())

            print('smartphone_json: ', smartphone_json)
            print('rating_json: ', rating_json)

            # remove id dari data json
            filtered_rating_data = [
                obj for obj in rating_json if 'user_id' in obj]
            filtered_rating_data = [{key: value for key, value in obj.items(
            ) if key != 'id'} for obj in filtered_rating_data]

            rating_df = pd.DataFrame(filtered_rating_data)
            print('rating_df:\n', rating_df)
            # print(json.dumps(rating_json))
            # print(json.dumps(smartphone_json))

            new_format = []
            for index, row in rating_df.iterrows():
                # print(row)
                user_id = row['user_id']
                ratings = row.drop('user_id')

                for i, rating in enumerate(ratings):
                    new_format.append([user_id, str(2001 + i), rating])

            rate = pd.DataFrame(new_format, columns=[
                                'user_id', 'merk_id', 'rating'])
            phone = pd.DataFrame(smartphone_json)

            print('rate\n', rate)
            print('phone\n', phone)

            # rate['user_id'] = rate['user_id'].astype(int)
            # rate['rating'] = rate['rating'].astype(int)
            
            # exclude rating 0
            # rate = rate.loc[rate['rating'] > 0]

            # print(rate)

            sim_dict, data_brand_score = run_analyze(
                input_user_id, rate, phone)
            # sim_dict[user_id].pop(0)
            return Response({'Similarity_User': sim_dict, 'score_brand': data_brand_score})
        else:
            return Response(
                serializer.errors,
                status=status.HTTP_400_BAD_REQUEST
            )
