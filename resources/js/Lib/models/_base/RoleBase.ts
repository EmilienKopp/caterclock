import { Role } from '$models';

export class RoleBase implements Role {
    id: number;
    name: any;
    description?: string;
    is_default: boolean;
    is_protected: boolean;
    level: number;
    created_at?: any;
    updated_at?: any;


    constructor(data: Role) {
        this.id = data.id;
        this.name = data.name;
        this.description = data.description;
        this.is_default = data.is_default;
        this.is_protected = data.is_protected;
        this.level = data.level;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}