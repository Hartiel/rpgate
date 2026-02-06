from jose import JWTError, jwt
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from sqlalchemy.orm import Session

from app.core.config import settings
from app.core.database import get_db
from app.models.user import User
from app.schemas.auth_schema import AuthUserResponse

oauth2_scheme = OAuth2PasswordBearer(tokenUrl='/v1/auth/login')


def get_auth_user(
    db: Session = Depends(get_db),
    token: str = Depends(oauth2_scheme),
) -> AuthUserResponse:
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )

    try:
        payload = jwt.decode(
            token,
            settings.SECRET_KEY,
            algorithms=[settings.ALGORITHM],
        )
        email: str = payload.get("sub")
        if email is None:
            raise credentials_exception
    except JWTError:
        raise credentials_exception

    user = db.query(User).filter(User.email == email).first()

    if not user:
        raise credentials_exception

    auth_user = AuthUserResponse(
        id=user.id,
        email=user.email,
        username=user.username,
        name=user.name,
        avatar_url=user.avatar_url,
        bio=user.bio,
    )

    return auth_user
