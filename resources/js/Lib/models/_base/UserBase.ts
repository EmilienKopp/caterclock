import { User } from '$models';

export class UserBase implements User {
    id: number;
    name: any;
    email: any;
    email_verified_at?: any;
    password?: any;
    remember_token?: any;
    created_at?: any;
    updated_at?: any;
    avatar?: any;
    last_login?: any;
    timezone?: any;


    constructor(data: User) {
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
    }
}