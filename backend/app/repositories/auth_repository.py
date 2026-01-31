from http.client import HTTPException
from sqlite3 import IntegrityError
from typing import Optional
from sqlalchemy.orm import Session
from app.models.user import User

class AuthRepository:
    def __init__(self, db: Session):
        self.db = db

    def get_by_email(self, email: str) -> Optional[User]:
        return self.db.query(User).filter(User.email == email).first()

    def get_by_username(self, username: str) -> Optional[User]:
        return self.db.query(User).filter(User.username == username).first()

    def create_user(
            self,
            email: str,
            username: str,
            name: str | None,
            hashed_password: str
        ) -> User:

        user = User(
            email=email,
            hashed_password=hashed_password,
            name=name,
            username=username,
        )
        try:
            self.db.add(user)
            self.db.commit()
            self.db.refresh(user)

            return user
        except IntegrityError:
            self.db.rollback()
            raise HTTPException(
                status_code=400,
                detail="User with this email or username already exists."
            )