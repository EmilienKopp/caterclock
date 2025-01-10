import { Company, Role, ConnectionRequest } from '$models';

export class ConnectionRequestBase implements ConnectionRequest {
    id: number;
    sender_id: number;
    company_id?: number;
    company?: Company;
    receiver_id?: number;
    status: any;
    created_at?: any;
    updated_at?: any;
    role_id: number;
    role?: Role;


    constructor(data: ConnectionRequest) {
        this.id = data.id;
        this.sender_id = data.sender_id;
        this.company_id = data.company_id;
        this.receiver_id = data.receiver_id;
        this.status = data.status;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.role_id = data.role_id;
    }
}