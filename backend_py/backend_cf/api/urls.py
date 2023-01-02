from django.urls import path, include
from rest_framework.routers import DefaultRouter
from api import views
from api.views import SmartphoneApiView, RatingApiView, RatingDataView, user_cfView


# url api

app_name = 'users'

# router = DefaultRouter()
# router.register(r'ratings_edit', views.RatingViewset)

urlpatterns = [
    # path('api/', include(router.urls)),
    path('Gawaiku', views.HelloApiView.as_view()),
    path('smartphone/', views.SmartphoneApiView.as_view()),
    path('ratings/', views.RatingApiView.as_view()),
    path('ratings_edit/<int:pk>/',
         views.RatingViewset.as_view(), name='rating-detail'),
    path('ratings_data/<int:pk>/', views.RatingDataView.as_view()),
    path('user_cf/', views.user_cfView.as_view()),
    # path('get_current_id/', views.get_current_id, name='get_current_id'),

]
