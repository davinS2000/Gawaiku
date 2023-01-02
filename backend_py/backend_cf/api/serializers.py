from rest_framework import serializers
from django.contrib.auth import get_user_model
from api.models import Smartphone, Rating


class HelloSerializer(serializers.Serializer):
    """Serializers a name field for testing our APIView"""

    user_id = serializers.IntegerField()


# class SmartphoneSerializer(serializers.ModelSerializer):
#     merk_id = serializers.IntegerField()
#     nama_merk = serializers.CharField(max_length=60)
#     tipe = serializers.CharField(max_length=30)

class SmartphoneSerializer(serializers.ModelSerializer):
    class Meta:
        model = Smartphone
        fields = ('merk_id', 'nama_merk', 'tipe')


# class RatingSerializer(serializers.ModelSerializer):
#     user_id = serializers.IntegerField()
#     merk_id1 = serializers.IntegerField()
#     merk_id2 = serializers.IntegerField()
#     merk_id3 = serializers.IntegerField()
#     merk_id4 = serializers.IntegerField()
#     merk_id5 = serializers.IntegerField()

class RatingSerializer(serializers.ModelSerializer):
    class Meta:
        model = Rating
        fields = ('user_id', 'merk_id1', 'merk_id2',
                  'merk_id3', 'merk_id4', 'merk_id5')
        # read_only_fields = ['user_id']


class RatingDataSerializer(serializers.ModelSerializer):
    class Meta:
        model = Rating
        fields = '__all__'
        # fields = ('user_id',)


# class RatingPostSerializer(serializers.ModelSerializer):
#     class Meta:
#         model = Rating
#         fields = ('merk_id1', 'merk_id2',
#                   'merk_id3', 'merk_id4', 'merk_id5')
