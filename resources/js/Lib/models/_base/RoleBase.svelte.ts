import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, IRole, ITimeLog } from '$models';

export class RoleBase implements IRole {
    id = $state<number>(0);
    name = $state<any>();
    description? = $state<string>('');
    is_default = $state<boolean>(false);
    is_protected = $state<boolean>(false);
    level = $state<number>(0);
    created_at? = $state<any>();
    updated_at? = $state<any>();

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: IRole) {
        this.id = data.id;
        this.name = data.name;
        this.description = data.description;
        this.is_default = data.is_default;
        this.is_protected = data.is_protected;
        this.level = data.level;
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