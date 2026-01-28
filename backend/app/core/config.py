from pydantic import PostgresDsn
from pydantic_settings import BaseSettings, SettingsConfigDict

import os

class Settings(BaseSettings):
    DATABASE_URL: PostgresDsn
    DEBUG: bool = True
    PROJECT_NAME: str = 'RPGate'
    SECRET_KEY: str = os.getenv('SECRET_KEY')
    ALGORITHM: str = os.getenv('ALGORITHM')
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 60 * 24 * 7  # 8 days

    model_config = SettingsConfigDict(env_file=".env", extra="ignore")

settings = Settings()