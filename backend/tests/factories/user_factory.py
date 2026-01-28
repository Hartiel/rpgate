from app.models.user import User
from app.core.security import get_password_hash

def create_user(db, *, email: str, password: str, username: str = "testuser", name: str | None = None):
    user = User(
        email=email,
        username=username,
        name=name,
        hashed_password=get_password_hash(password),
        is_active=True,
        is_verified=False,
    )
    db.add(user)
    db.flush()
    db.refresh(user)
    return user
