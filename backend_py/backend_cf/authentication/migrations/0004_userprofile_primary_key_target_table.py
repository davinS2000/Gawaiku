# Generated by Django 4.1.3 on 2022-12-18 11:09

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('authentication', '0003_profilefeeditem'),
    ]

    operations = [
        migrations.AddField(
            model_name='userprofile',
            name='primary_key_target_table',
            field=models.IntegerField(blank=True, null=True),
        ),
    ]