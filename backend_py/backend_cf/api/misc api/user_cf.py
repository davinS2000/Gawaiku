# import csv
# import requests
import pandas as pd
# from views import RatingApiView, SmartphoneApiView
# from .views import RatingApiView, SmartphoneApiView

# rating = 'C:/xampp/htdocs/1_test/Web_CF/backend_py/backend_cf/api/test_raw_data.csv'
# smartphone = 'C:/xampp/htdocs/1_test/Web_CF/backend_py/backend_cf/api/smartphone.csv'

# """
# rating_url = 'http://127.0.0.1:8000/api/ratings/'
# smartphone_url = 'http://127.0.0.1:8000/api/smartphone/'

# rating_url = RatingApiView.object.all()
# smartphone_url = SmartphoneApiView.object.all()

# # smartphone_url = 'http://127.0.0.1:8000/api/smartphone/'

# rating_response = requests.get(rating_url)
# smartphone_response = requests.get(smartphone_url)


# # rating_data = rating_response.json()
# # smartphone_data = smartphone_response.json()

# rating_data = rating_response.json()
# smartphone_data = smartphone_response.json()

# rating_df = pd.DataFrame(rating_data)

# # print(rating_response.text)
# # print(smartphone_response.text)

# # print(rating_df.head(5))

# # print(smartphone_data)

# # data untuk menampung value format baru
# new_format = []

# for index, row in rating_df.iterrows():
#     user_id = row['user_id']
#     ratings = row.drop('user_id')

#     for i, rating in enumerate(ratings):
#         new_format.append([user_id, str(2001 + i), rating])

# rate = pd.DataFrame(new_format, columns=['user_id', 'merk_id', 'rating'])
# phone = pd.DataFrame(smartphone_data)


# # 1 read data
# # menampilkan 5 data rating teratas, index dari 0
# # print(rate.head(10)), kalau ingin menampilkan 10 data
# # print(rate.head(5), '\n')

# # info data rating
# # rate.info()

# # exclude 0 dari rate
# # convert type str ke int
# rate['user_id'] = rate['user_id'].astype(int)
# rate['rating'] = rate['rating'].astype(int)
# rate = rate.loc[rate['rating'] > 0]

# # print(rate.head(10))

# rate.info()
# """


def run_analyze(user_id, rate, phone):
    # def run_analyze(user_id):
    # pengecekan data user
    # num of user
    print(f"\nrating pada dataset: {rate['user_id'].nunique()} unique user")

    # num of merk
    print(f"rating pada dataset: {rate['merk_id'].nunique()} unique merk")

    # num of ratings
    print(f"rating pada dataset: {rate['rating'].nunique()} unique rating")

    # list of unique rating (list rating pada rating.csv): 0,3,4,5
    print(f"list rating pada dataset: {sorted(rate['rating'].unique())}\n")

    # read data merk
    print(phone.head(), '\n')

    # convert merk_id sesuai dengan type merk_id di kedua dataFrame
    if rate['merk_id'].dtype != phone['merk_id'].dtype:
        if rate['merk_id'].dtype == 'object':
            rate['merk_id'] = rate['merk_id'].astype(phone['merk_id'].dtype)

        else:
            phone['merk_id'] = phone['merk_id'].astype(rate['merk_id'].dtype)

    df = pd.merge(rate, phone, on='merk_id', how='inner')

    # menampilkan hasil merge
    print('hasil merge:\n', df.head(10), '\n')

    # 2 Exploratory data analysis
    # filter the brand and keep only those with-
    # -over 1 ratings for the analysis
    # aggregate by brand
    agg_rate = df.groupby('nama_merk').agg(mean_rate=('rating', 'mean'),
                                           num_of_rate=('rating', 'count')).reset_index()
    print('\n>>>', agg_rate, '\n')

    # print(agg_rate.info())

    # filter brand dengan rating > 5
    agg_rate_gt = agg_rate[agg_rate['num_of_rate'] > 5]
    agg_rate_gt.info()

    # cek merk popular beserta rating
    agg_sort = agg_rate_gt.sort_values(by='num_of_rate', ascending=False)
    print('\n', agg_sort.head(), '\n')

    # jointplot untuk cek korelasi antar avg rating dengan num of rating
    # visualization
    import seaborn as sns
    import matplotlib.pyplot as plt
    sns.jointplot(x='mean_rate', y='num_of_rate', data=agg_rate_gt)

    # join phone dan rating untuk-
    # -menyimpan 5 entry dengan rating lebih dari 1

    # merge data
    df_gt = pd.merge(
        df, agg_rate_gt[['nama_merk']], on='nama_merk', how='inner')

    df_gt.info()

    # After filtering the movies with over 100 ratings,
    # we have 597 users that rated 134 movies

    # setelah memfilter brand dengan lebih dari 5 rating,
    # sekarang terdapat 56 user yang telah merating 4 brand

    # num of user
    print(f"\nrating pada dataset: {df_gt['user_id'].nunique()} unique user")

    # num of movies
    print(f"rating pada dataset: {df_gt['merk_id'].nunique()} unique merk")

    # num of ratings
    print(f"rating pada dataset: {df_gt['rating'].nunique()} unique rating")

    # list of unique rating
    print(f"list rating pada dataset: {sorted(df_gt['rating'].unique())}\n")

    # 3 create user_brand matrix
    matrix = df_gt.pivot_table(
        index='user_id', columns='nama_merk', values='rating')

    print('matrix:\n', matrix.head(), '\n')

    # 4 data normalization
    # normalize user-item matrix
    matrix_norm = matrix.subtract(matrix.mean(axis=1), axis='rows')

    # jumlah 5 row atas
    print('-5 row atas\n', matrix_norm.head(), '\n')

    # jumlah 5 row bawah
    print('-5 row bawah\n', matrix_norm.tail(), '\n')

    # 5 identify similar user
    # user similarity menggunakan pearson correlation

    user_sim = matrix_norm.T.corr()
    print('view similarity', user_sim.head(), '\n')

    # user_id = input('input user_id: ')

    picked_user_id = user_id
    # picked_user_id = str(1004)

    # remove picked user id from the candidate list
    user_sim.drop(index=picked_user_id, inplace=True)

    # take a look at the data (display)
    print(f"remove id = {user_id}, dan view:")
    print(user_sim.head(), '\n')

    # n = 3, artinya memilih 3 user yang mirip dengan user id 1004
    # user_similarity_threshold = 0.3, artinya user harus-
    # setidaknya memiliki similarity sebanyak 0.3 untuk dianggap mirip
    # setelah set similarity user dan similarity threshold,
    # sort dari besar ke kecil dan print yang paling mirip

    # num of sim user
    n = 5
    # user sim threshold
    user_sim_threshold = 0.3

    import warnings
    warnings.simplefilter(action='ignore', category=FutureWarning)

    # get top n sim
    sim_user = user_sim[user_sim[picked_user_id] >
                        user_sim_threshold][picked_user_id].sort_values(ascending=False)[:n]

    print('sim_user:\n', sim_user)
    sim_user.info()

    # print out top n sim user
    # print(f"user yang mirip dengan {picked_user_id}:\n{sim_user}")

    # convert sim user into dictionary, untuk di taro di return

    sim_series = pd.Series([sim_user])
    sim_list = sim_series.tolist()
    print('sim_list:\n', sim_list)
    # Add picked_user_id to the end of the sim_list
    # loop untuk menambahkan user id yg sim dan score ke list
    sim_user_dict = {}
    for user_id, similarity_score in sim_user.items():
        # sim_list.append(user_id)
        # sim_list.append(similarity_score)
        sim_user_dict[user_id] = similarity_score

    sim_list.append(sim_user_dict)
    sim_list.pop(0)
    sim_dict = {picked_user_id: tuple(sim_list)}

    # 6 narrow down item pool
    # merk hp yang pernah dipakai

    picked_user_id_brand_used = matrix_norm[matrix_norm.index ==
                                            picked_user_id].dropna(axis=1, how='all')
    # picked_user_id_brand_used

    # merk sim yang telah dipakai user, hilangkan merk yang tidak sim

    sim_user_brand = matrix_norm[matrix_norm.index.isin
                                 (sim_user.index)].dropna(axis=1, how='all')

    # sim_user_brand
    print('sim_user_brand:\n', sim_user_brand)

    # 7 recommend brand

    # dictionary untuk menyimpan score brand

    brand_score = {}

    for i in sim_user_brand.columns:
        # get the rating for brand i
        brand_rating = sim_user_brand[i]

        # buat variable untuk menyimpan score
        total = 0

        # buat variable untuk menyimpan jumlah score
        count = 0

        # loop seluruh sim user
        for u in sim_user.index:

            # jika merknya punya rating
            if pd.isna(brand_rating[u]) == False:
                # Scorenya adalah jumlah score user sim dikali dengan rating brand
                score = sim_user[u] * brand_rating[u]

                # tambah score ke total score untuk brand sejauh ini
                total += score

                # increment 1 ke count
                count += 1

                # dapat score rata2 untuk brand
                brand_score[i] = total / count

    print('brand_score:\n', brand_score)

    # konversi dictionary ke dataframe pandas
    brand_score = pd.DataFrame(brand_score.items(), columns=[
                               'brand', 'brand_score'])

    # sort brand berdasarkan scorenya
    ranked_brand_score = brand_score.sort_values(
        by='brand_score', ascending=False)
    print('#Ranked brand score')
    print(ranked_brand_score)

    # pilih top x brand

    x = 5
    # print(ranked_brand_score.head(x))
    data_brand_score = ranked_brand_score.head(x)
    # 8 Prediksi Score
    # rata2 rating untuk user yang dipilih
    avg_rating = matrix[matrix.index == picked_user_id].T.mean()[
        picked_user_id]

    # print rata2 rating brand untuk user 1
    print(
        f"\nrata2 rating merk untuk user {picked_user_id} adalah {avg_rating:.2f}")
    # print(ranked_brand_score['brand_score'], type(
    #     ranked_brand_score['brand_score']), dir(ranked_brand_score['brand_score']))

    print(ranked_brand_score['brand_score'])

    # kalkulasi prediksi rating
    ranked_brand_score['predicted rating'] = ranked_brand_score['brand_score'] + avg_rating
    ranked_brand_score.head(x)


# return data_brand_score.to_dict('dict'), sim_dict
    return sim_dict, data_brand_score.to_dict('dict')
