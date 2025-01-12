import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITimeLog, IUser } from '$models';

export class IdentityBase implements IIdentity {
    id = $state<number>(0);
    user_id = $state<number>(0);
    user?: IUser;
    oauth_id = $state<string>('');
    access_token? = $state<string>('');
    refresh_token? = $state<string>('');
    expires_in? = $state<string>('');
    created_at? = $state<any>();
    updated_at? = $state<any>();
    oauth_provider = $state<any>();

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: IIdentity) {
        this.id = data.id;
        this.user_id = data.user_id;
        this.oauth_id = data.oauth_id;
        this.access_token = data.access_token;
        this.refresh_token = data.refresh_token;
        this.expires_in = data.expires_in;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.oauth_provider = data.oauth_provider;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}