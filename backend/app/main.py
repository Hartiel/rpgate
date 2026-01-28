from fastapi import FastAPI

from app.api.v1 import auth_route

app = FastAPI()

app.include_router(prefix='/v1', router=auth_route.router)
