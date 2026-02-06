from typing import Optional
from pydantic import BaseModel, EmailStr, Field, UUID4


class RegisterRequest(BaseModel):
    email: EmailStr
    password: str = Field(min_length=6)
    username: str = Field(min_length=3, max_length=16)
    name: Optional[str] = Field(default=None, max_length=100)

class LoginRequest(BaseModel):
    email: EmailStr
    password: str

class TokenResponse(BaseModel):
    access_token: str
    token_type: str = "bearer"

class AuthUserResponse(BaseModel):
    id: UUID4
    email: EmailStr
    username: str = Field(min_length=3, max_length=16)
    name: Optional[str] = Field(default=None, max_length=100)
    avatar_url: Optional[str]
    bio: Optional[str]
