import { User, Identity } from '$models';

export class IdentityBase implements Identity {
    id: number;
    user_id: number;
    user?: User;
    oauth_id: string;
    access_token?: string;
    refresh_token?: string;
    expires_in?: string;
    created_at?: any;
    updated_at?: any;
    oauth_provider: any;


    constructor(data: Identity) {
        this.id = data.id;
        this.user_id = data.user_id;
        this.oauth_id = data.oauth_id;
        this.access_token = data.access_token;
        this.refresh_token = data.refresh_token;
        this.expires_in = data.expires_in;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.oauth_provider = data.oauth_provider;
    }
}