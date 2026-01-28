
import pytest
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker

from app.core.database import Base, get_db
from app.main import app

# Cria engine SQLite em memória para testes
SQLALCHEMY_DATABASE_URL = "sqlite+pysqlite:///:memory:"
engine = create_engine(SQLALCHEMY_DATABASE_URL, connect_args={"check_same_thread": False})
TestingSessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

@pytest.fixture(scope="session", autouse=True)
def setup_db():
	# Cria as tabelas no banco de teste
	Base.metadata.create_all(bind=engine)
	yield
	# Dropa as tabelas após os testes
	Base.metadata.drop_all(bind=engine)

@pytest.fixture(scope="function")
def db_session():
	connection = engine.connect()
	transaction = connection.begin()
	
	db = TestingSessionLocal(bind=connection)
	
	try:
		yield db
	finally:
		db.close()
		transaction.rollback()
		connection.close()

# Sobrescreve a dependência get_db para usar o banco de teste
@pytest.fixture(scope="function")
def test_app(db_session):
    def override_get_db():
        yield db_session

    app.dependency_overrides[get_db] = override_get_db
    yield app
    app.dependency_overrides.clear()
