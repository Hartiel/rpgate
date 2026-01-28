from fastapi import HTTPException, status
from sqlalchemy.orm import Session

from app.core.security import create_access_token, get_password_hash, verify_password
from app.repositories.auth_repository import AuthRepository
from app.schemas.auth_schema import LoginRequest, RegisterRequest, TokenResponse

def register_user(db: Session, data: RegisterRequest) -> TokenResponse:
    repo = AuthRepository(db)

    if repo.get_by_email(data.email):
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Email already registered",
        )
    if repo.get_by_username(data.username):
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Username already taken",
        )
    
    hashed_password = get_password_hash(data.password)
    user = repo.create_user(
        email=data.email,
        username=data.username,
        name=data.name,
        hashed_password=hashed_password,
    )

    token = create_access_token(subject=user.email)
    return TokenResponse(access_token=token)

def login_user(db: Session, data: LoginRequest) -> TokenResponse:
    repo = AuthRepository(db)
    user = repo.get_by_email(data.email)

    if not user or not verify_password(data.password, user.hashed_password):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid credentials",
        )

    token = create_access_token(subject=user.email)
    return TokenResponse(access_token=token)