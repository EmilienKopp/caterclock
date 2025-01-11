export interface IActivity {
    id: number;
    project_id: number;
    project?: Project;
    user_id: number;
    user?: User;
    task_category_id: number;
    task_category?: TaskCategory;
    task_id?: number;
    task?: Task;
    date?: Date;
    comment?: string;
    start_time?: any;
    end_time?: any;
    duration?: number;
    rate?: number;
    created_at?: any;
    updated_at?: any;
    hours?: number;
    minutes?: number;
}

export interface ICompany {
    id: number;
    name: string;
    address?: string;
    contact_email: string;
    created_at?: any;
    updated_at?: any;
    representative_id: number;
    contact_phone?: any;
    employees_count?: number;
    is_public: boolean;
    is_active: boolean;
    code?: any;
    corporate_number?: any;

    projects?: Project[];
    connectionRequests?: ConnectionRequest[];
}

export interface IConnectionRequest {
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
}

export interface IDailyLog {
    user_id?: number;
    user?: User;
    user_name?: any;
    project_id?: number;
    project?: Project;
    project_name?: string;
    date?: any;
    total_seconds?: number;
    total_minutes?: number;
    is_running?: boolean;
}

export interface IIdentity {
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
}

export interface IPermission {
    id: number;
    role_id: number;
    role?: Role;
    ability: any;
    model: any;
    created_at?: any;
    updated_at?: any;
}

export interface IPosition {
    company_id: number;
    company?: Company;
    user_id: number;
    user?: User;
    created_at?: any;
    updated_at?: any;
    role_id: number;
    role?: Role;
}

export interface IProject {
    id: number;
    company_id?: number;
    company?: Company;
    user_id?: number;
    user?: User;
    name: string;
    description?: string;
    start_date?: Date;
    end_date?: Date;
    created_at?: any;
    updated_at?: any;
    budget_low?: number;
    budget_mid?: number;
    budget_high?: number;
    spent?: number;
    balance_low?: number;
    balance_mid?: number;
    balance_high?: number;
    currency: any;

    tasks?: Task[];
    timeLogs?: TimeLog[];
    activities?: Activity[];
    rates?: Rate[];
}

export interface IRate {
    id: number;
    project_id: number;
    project?: Project;
    user_id: number;
    user?: User;
    task_category_id?: number;
    task_category?: TaskCategory;
    rate: number;
    currency: any;
    valid_from?: Date;
    valid_to?: Date;
    created_at?: any;
    updated_at?: any;
}

export interface IReport {
    id: number;
    created_at?: any;
    updated_at?: any;
}

export interface IRole {
    id: number;
    name: any;
    description?: string;
    is_default: boolean;
    is_protected: boolean;
    level: number;
    created_at?: any;
    updated_at?: any;

    permissions?: Permission[];
    connectionRequests?: ConnectionRequest[];
}

export interface ITask {
    id: number;
    project_id: number;
    project?: Project;
    category_id: number;
    name: string;
    description?: string;
    rate?: number;
    estimated_time?: any;
    actual_time?: any;
    date?: Date;
    start_time?: any;
    end_time?: any;
    due_date?: any;
    status: string;
    created_at?: any;
    updated_at?: any;

    activities?: Activity[];
}

export interface ITaskCategory {
    id: number;
    name: string;
    description?: string;
    created_at?: any;
    updated_at?: any;

    tasks?: Task[];
    activities?: Activity[];
    rates?: Rate[];
}

export interface ITimeLog {
    id: number;
    user_id: number;
    user?: User;
    project_id: number;
    project?: Project;
    date: any;
    in_time: any;
    out_time?: any;
    break_start?: any;
    break_end?: any;
    break_duration?: number;
    total_duration?: number;
    is_running: boolean;
    notes?: string;
    created_at?: any;
    updated_at?: any;
    timezone?: any;
}

export interface IUser {
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

    projects?: Project[];
    timeLogs?: TimeLog[];
    activities?: Activity[];
    companies?: Company[];
    connectionRequests?: ConnectionRequest[];
    rates?: Rate[];
    identities?: Identity[];
}

export type ModelTypes = Activity | Company | ConnectionRequest | DailyLog | Identity | Permission | Position | Project | Rate | Report | Role | Task | TaskCategory | TimeLog | User;