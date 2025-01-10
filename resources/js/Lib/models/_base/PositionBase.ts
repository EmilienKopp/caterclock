import { Company, User, Role, Position } from '$models';

export class PositionBase implements Position {
    company_id: number;
    company?: Company;
    user_id: number;
    user?: User;
    created_at?: any;
    updated_at?: any;
    role_id: number;
    role?: Role;


    constructor(data: Position) {
        this.company_id = data.company_id;
        this.user_id = data.user_id;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.role_id = data.role_id;
    }
}