import type { IActivity, ICompany, IConnectionRequest, IIdentity, IPermission, IProject, IRate, IRole, ITimeLog } from '$models';

export class PermissionBase implements IPermission {
    id: number;
    role_id: number;
    role?: IRole;
    ability: any;
    model: any;
    created_at?: any;
    updated_at?: any;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: Permission) {
        this.id = data.id;
        this.role_id = data.role_id;
        this.ability = data.ability;
        this.model = data.model;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}