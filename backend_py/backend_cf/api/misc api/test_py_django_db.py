import requests
import pandas as pd

rating_url = 'http://127.0.0.1:8000/api/ratings/'
smartphone_url = 'http://127.0.0.1:8000/api/smartphone/'

rating_response = requests.get(rating_url)
smartphone_response = requests.get(smartphone_url)

# print(rating_response.text)

rating_data = rating_response.json()
smartphone_data = smartphone_response.json()

# print('\n', rating_data)
rate = pd.DataFrame(rating_data)

print(rate.head(5))
