import { Role, Permission } from '$models';

export class PermissionBase implements Permission {
    id: number;
    role_id: number;
    role?: Role;
    ability: any;
    model: any;
    created_at?: any;
    updated_at?: any;


    constructor(data: Permission) {
        this.id = data.id;
        this.role_id = data.role_id;
        this.ability = data.ability;
        this.model = data.model;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}