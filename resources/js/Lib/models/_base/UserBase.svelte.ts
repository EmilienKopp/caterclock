import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITimeLog, IUser } from '$models';

export class UserBase implements IUser {
    id = $state<number>(0);
    name = $state<any>();
    email = $state<any>();
    email_verified_at? = $state<any>();
    password? = $state<any>();
    remember_token? = $state<any>();
    created_at? = $state<any>();
    updated_at? = $state<any>();
    avatar? = $state<any>();
    last_login? = $state<any>();
    timezone? = $state<any>();

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: IUser) {
        this.id = data.id;
        this.name = data.name;
        this.email = data.email;
        this.email_verified_at = data.email_verified_at;
        this.password = data.password;
        this.remember_token = data.remember_token;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.avatar = data.avatar;
        this.last_login = data.last_login;
        this.timezone = data.timezone;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}