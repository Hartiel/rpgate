import pytest
from httpx import AsyncClient, ASGITransport
from tests.factories.user_factory import create_user

@pytest.mark.asyncio
async def test_login_route(test_app, db_session):
    create_user(
        db_session,
        email="testuser@example.com",
        password="testpass",
        username="testuser",
    )
    
    transport = ASGITransport(app=test_app)
    async with AsyncClient(transport=transport, base_url="http://test") as ac:
        response = await ac.post(
            "/v1/auth/login",
            json={"email": "testuser@example.com", "password": "testpass"})
        
        assert response.status_code == 200
        data = response.json()
        assert "access_token" in data
        assert data["token_type"] == "bearer"

@pytest.mark.asyncio
async def test_login_wrong_password(test_app, db_session):
    create_user(
        db_session,
        email="testuser@example.com",
        password="testpass",
        username="testuser",
    )

    transport = ASGITransport(app=test_app)
    async with AsyncClient(transport=transport, base_url="http://test") as ac:
        response = await ac.post(
            "/v1/auth/login",
            json={"email": "testuser@example.com", "password": "wrong"},
        )

    assert response.status_code == 401
