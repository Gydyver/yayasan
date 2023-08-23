# encrypt_script.py
import sys

def encrypt_id(id):
    # Logika enkripsi di sini (sesuai kebutuhan Anda)
    encrypted_id = "encrypted_" + id
    return encrypted_id

if __name__ == "__main__":
    id_to_encrypt = sys.argv[1]
    encrypted_id = encrypt_id(id_to_encrypt)
    print(encrypted_id)
