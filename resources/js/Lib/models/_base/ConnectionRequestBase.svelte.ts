import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, IRole, ITimeLog } from '$models';

export class ConnectionRequestBase implements IConnectionRequest {
    id = $state<number>(0);
    sender_id = $state<number>(0);
    company_id? = $state<number>(0);
    company?: ICompany;
    receiver_id? = $state<number>(0);
    status = $state<any>();
    created_at? = $state<any>();
    updated_at? = $state<any>();
    role_id = $state<number>(0);
    role?: IRole;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: IConnectionRequest) {
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