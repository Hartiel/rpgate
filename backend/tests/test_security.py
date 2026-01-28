from app.core import security

def test_get_password_hash_and_verify():
    password = "mysecretpassword"
    hashed = security.get_password_hash(password)
    assert isinstance(hashed, str)
    assert security.verify_password(password, hashed)

def test_create_access_token():
    subject = "user_id"
    token = security.create_access_token(subject)
    assert isinstance(token, str)
