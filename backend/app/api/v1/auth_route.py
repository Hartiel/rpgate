from fastapi import APIRouter, Depends
from fastapi.security import OAuth2PasswordRequestForm
from sqlalchemy.orm import Session

from app.core.database import get_db
from app.schemas.auth_schema import AuthUserResponse, LoginRequest, TokenResponse, RegisterRequest
from app.services.auth_service import login_user, register_user
from app.deps.auth_dep import get_auth_user
from app.models.user import User

router = APIRouter(prefix="/auth", tags=["auth"])

@router.post('/register', response_model=TokenResponse)
def register(data: RegisterRequest, db: Session = Depends(get_db)) -> TokenResponse:
    return(register_user(db, data))

@router.post("/login", response_model=TokenResponse)
def login(form_data: OAuth2PasswordRequestForm = Depends(), db: Session = Depends(get_db)) -> TokenResponse:
    data = LoginRequest(email=form_data.username, password=form_data.password)
    return login_user(db, data)

@router.get('/me')
def auth_user(current_user: User = Depends(get_auth_user)) -> AuthUserResponse:
    return current_user