# Generated by Django 4.1.3 on 2022-12-18 11:35

from django.db import migrations, models


class Migration(migrations.Migration):

    initial = True

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='Rating',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('user_id', models.IntegerField()),
                ('merk_id1', models.IntegerField(default=0)),
                ('merk_id2', models.IntegerField(default=0)),
                ('merk_id3', models.IntegerField(default=0)),
                ('merk_id4', models.IntegerField(default=0)),
                ('merk_id5', models.IntegerField(default=0)),
            ],
        ),
        migrations.CreateModel(
            name='Smartphone',
            fields=[
                ('merk_id', models.IntegerField(default=0, primary_key=True, serialize=False)),
                ('nama_merk', models.CharField(max_length=60)),
                ('tipe', models.CharField(max_length=30)),
            ],
        ),
    ]
