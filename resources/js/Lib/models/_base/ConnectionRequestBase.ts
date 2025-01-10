import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, IRole, ITimeLog } from '$models';

export class ConnectionRequestBase implements IConnectionRequest {
    id: number;
    sender_id: number;
    company_id?: number;
    company?: ICompany;
    receiver_id?: number;
    status: any;
    created_at?: any;
    updated_at?: any;
    role_id: number;
    role?: IRole;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: ConnectionRequest) {
        this.id = data.id;
        this.sender_id = data.sender_id;
        this.company_id = data.company_id;
        this.receiver_id = data.receiver_id;
        this.status = data.status;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.role_id = data.role_id;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}