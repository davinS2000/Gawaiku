from django.db import models


# Create your models here.


class Smartphone(models.Model):
    """Database model for users in the system"""
    merk_id = models.IntegerField(primary_key=True, default=0)
    nama_merk = models.CharField(max_length=60)
    tipe = models.CharField(max_length=30)


class Rating(models.Model):
    """Database model for users in the system"""
    # id = models.AutoField(primary_key=True)
    user_id = models.IntegerField()
    merk_id1 = models.IntegerField(default=0)
    merk_id2 = models.IntegerField(default=0)
    merk_id3 = models.IntegerField(default=0)
    merk_id4 = models.IntegerField(default=0)
    merk_id5 = models.IntegerField(default=0)
