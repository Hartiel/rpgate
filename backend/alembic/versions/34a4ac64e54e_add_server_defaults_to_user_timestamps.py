"""add_server_defaults_to_user_timestamps

Revision ID: 34a4ac64e54e
Revises: 059c36d0a4ee
Create Date: 2026-01-31 11:54:24.145183

"""
from typing import Sequence, Union

from alembic import op
import sqlalchemy as sa


# revision identifiers, used by Alembic.
revision: str = '34a4ac64e54e'
down_revision: Union[str, Sequence[str], None] = '059c36d0a4ee'
branch_labels: Union[str, Sequence[str], None] = None
depends_on: Union[str, Sequence[str], None] = None


def upgrade() -> None:
    """Upgrade schema."""
    op.alter_column('users', 'created_at', 
               existing_type=sa.DateTime(timezone=True),
               server_default=sa.text('now()'),
               nullable=False)
    
    op.alter_column('users', 'updated_at', 
               existing_type=sa.DateTime(timezone=True),
               server_default=sa.text('now()'),
               nullable=False)
    pass
    # ### end Alembic commands ###


def downgrade() -> None:
    """Downgrade schema."""
    op.alter_column('users', 'created_at', 
               existing_type=sa.DateTime(timezone=True),
               server_default=None)
    
    op.alter_column('users', 'updated_at', 
               existing_type=sa.DateTime(timezone=True),
               server_default=None)
    pass
    # ### end Alembic commands ###
