from django.urls import path, include
from rest_framework.routers import DefaultRouter
from rest_framework_simplejwt.views import (
    TokenObtainPairView,
    TokenRefreshView
)
from authentication import views

app_name = 'users'

router = DefaultRouter()
router.register('profile', views.UserProfileViewSet)
router.register('feed', views.UserProfileFeedViewSet)

urlpatterns = [
    path('authentication/', include(router.urls)),
    path('authentication/user_id/', views.UserView.as_view(), name='user'),
    # path('authentication/create/', views.CreateUserView.as_view(), name='create'),
    path('authentication/register/', views.RegisterAPIView.as_view()),
    path('authentication/login/', views.LoginAPIView.as_view()),
    path('authentication/test/', views.AuthenticatedAPIView.as_view()),
    path('authentication/refresh/', TokenRefreshView.as_view()),
    path('api/token/', TokenObtainPairView.as_view(), name='token_obtain_pair'),
    # path('init-auth', views.InitUserView.as_view())
]
