import requests
from api.test_ML_cf import run_analyze
user_id = int(input("ini input: "))

sim_dict, data_brand_score = run_analyze(user_id)
# data_brand_score_2, picked_user_id_brand_used_2 = run_analyze(1004)

# print("=============================")
print(sim_dict)
print(data_brand_score)

# print(data_brand_score_2)
# print(picked_user_id_brand_used_2)
