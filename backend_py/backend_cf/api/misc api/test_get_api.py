import requests

response_id = requests.get('http://127.0.0.1:8000/api/Gawaiku?value=$value')

print(response_id.text)

# rating_url = 'http://127.0.0.1:8000/api/ratings/'
# smartphone_url = 'http://127.0.0.1:8000/api/smartphone/'

# rating_response = requests.get(rating_url)
# smartphone_response = requests.get(smartphone_url)


# rating_data = rating_response.json()
# smartphone_data = smartphone_response.json()

# print(rating_data.text)
