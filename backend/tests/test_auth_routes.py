import pytest
from httpx import AsyncClient, ASGITransport
from tests.factories.user_factory import create_user

@pytest.mark.asyncio
async def test_login_route(test_app, db_session):
    create_user(
        db_session,
        email='testuser@example.com',
        password='testpass',
        username='testuser',
    )
    
    transport = ASGITransport(app=test_app)
    async with AsyncClient(transport=transport, base_url='http://test') as ac:
        response = await ac.post(
            '/v1/auth/login',
            data={'username': 'testuser@example.com', 'password': 'testpass'})
        
        assert response.status_code == 200
        data = response.json()
        assert 'access_token' in data
        assert data['token_type'] == 'bearer'

@pytest.mark.asyncio
async def test_login_wrong_password(test_app, db_session):
    create_user(
        db_session,
        email='testuser@example.com',
        password='testpass',
        username='testuser',
    )

    transport = ASGITransport(app=test_app)
    async with AsyncClient(transport=transport, base_url='http://test') as ac:
        response = await ac.post(
            '/v1/auth/login',
            data={'username': 'testuser@example.com', 'password': 'wrong'},
        )

    assert response.status_code == 401

@pytest.mark.asyncio
async def test_register_route(test_app, db_session):
    transport = ASGITransport(app=test_app)
    async with AsyncClient(transport=transport, base_url='http://test') as ac:
        response = await ac.post(
            '/v1/auth/register',
            json={
                'email': 'testuser@example.com',
                'username': 'testuser',
                'password': 'testpass',
                'name': 'Test user',
            },
        )

    data = response.json()

    assert response.status_code == 200
    assert 'access_token' in data
    assert data['token_type'] == 'bearer'

@pytest.mark.asyncio
async def test_get_auth_user(test_app, db_session):
    new_user = create_user(
        db_session,
        email='testuser@example.com',
        password='testpass',
        username='testuser',
    )

    transport = ASGITransport(app=test_app)

    async with AsyncClient(transport=transport, base_url='http://test') as ac:
        login_response = await ac.post(
            '/v1/auth/login',
            data={'username': 'testuser@example.com', 'password': 'testpass'},
        )

    data = login_response.json()

    async with AsyncClient(transport=transport, base_url='http://test') as ac:
        auth_response = await ac.get('/v1/auth/me', headers={'Authorization': f'Bearer {data["access_token"]}'})
    
    auth_data = auth_response.json()

    assert auth_response.status_code == 200
    assert auth_data['email'] == new_user.email
    assert auth_data['username'] == new_user.username