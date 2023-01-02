import mysql.connector
# 1 mysql connection
conn = mysql.connector.connect(
    host="localhost", port="3306", user="root", password="", database="test")

# 2 make cursor
cursor = conn.cursor()

selectquery = "select * from "

cursor.execute(selectquery)

records = cursor.fetchall()

print("tempat wisata:", cursor.rowcount)

for row in records:
    print("Id:", row[0])
    print("Nama:", row[1])
    print("alamat:", row[2])
    print()

cursor.close()
conn.close()
