export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';

export interface ApiClientOptions {
  baseUrl: string;
  token?: string;
  getToken?: () => string | null | undefined;
  fetchImpl?: typeof fetch;
}

export interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  count: number;
  sort_by?: string | null;
  sort_direction?: 'asc' | 'desc' | null;
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: PaginationMeta;
}

export interface ApiErrorPayload {
  message?: string;
  errors?: Record<string, string[]>;
}

export class EtuderapideApiError extends Error {
  status: number;
  payload: ApiErrorPayload | null;

  constructor(status: number, message: string, payload: ApiErrorPayload | null = null) {
    super(message);
    this.name = 'EtuderapideApiError';
    this.status = status;
    this.payload = payload;
  }
}

export interface LoginResponse {
  token: string;
  token_type: string;
  abilities: string[];
  user: {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    email_verified: boolean;
  };
}

export interface CurrentUser {
  id: number;
  name: string;
  email: string;
  is_admin: boolean;
  email_verified: boolean;
  roles: string[];
  abilities: string[];
}

export interface TokenRecord {
  id: number;
  name: string;
  abilities: string[];
  last_used_at: string | null;
  created_at: string | null;
  expires_at: string | null;
  is_current: boolean;
}

export interface CategoryRef {
  id: number;
  name: string;
  slug: string;
  type?: string;
}

export interface AuthorRef {
  id: number;
  name: string;
  email?: string;
}

export interface TagRef {
  id: number;
  name: string;
  slug: string;
}

export interface Lead {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  service_interest: string | null;
  message?: string;
  internal_notes?: string | null;
  payment_link?: string | null;
  quoted_value: number | null;
  status: 'new' | 'read' | 'replied' | 'archived';
  created_at?: string | null;
  updated_at?: string | null;
}

export interface PostItem {
  id: number;
  title: string;
  slug: string;
  body?: string;
  excerpt?: string;
  is_published?: boolean;
  published_at: string | null;
  seo_title: string | null;
  seo_description: string | null;
  featured_image: string | null;
  category: CategoryRef | null;
  author: AuthorRef | null;
  tags: TagRef[];
}

export interface ProjectItem {
  id: number;
  title: string;
  slug: string;
  summary: string | null;
  description?: string | null;
  status?: 'draft' | 'published' | 'archived';
  tech_stack: string[];
  demo_url: string | null;
  repository_url: string | null;
  started_at: string | null;
  finished_at: string | null;
  is_featured: boolean;
  seo_title?: string | null;
  seo_description?: string | null;
  category: CategoryRef | null;
  author: AuthorRef | null;
}

export interface ServiceItem {
  id: number;
  name: string;
  slug: string;
  short_description: string | null;
  full_description?: string | null;
  price_from: number | null;
  delivery_time: string | null;
  is_active?: boolean;
  call_to_action: string | null;
  seo_title?: string | null;
  seo_description?: string | null;
  category: CategoryRef | null;
  author: AuthorRef | null;
}

export interface DashboardSummary {
  posts_count: number;
  projects_count: number;
  services_count: number;
  total_leads_count: number;
  new_leads_count: number;
  total_pipeline_value: number;
  conversion_rate: number;
}

export type LeadFilters = Record<string, string | number | boolean | undefined>;
export type PostFilters = Record<string, string | number | boolean | undefined>;
export type ProjectFilters = Record<string, string | number | boolean | undefined>;
export type ServiceFilters = Record<string, string | number | boolean | undefined>;

export class EtuderapideApiClient {
  private baseUrl: string;
  private token?: string;
  private getToken?: () => string | null | undefined;
  private fetchImpl: typeof fetch;

  constructor(options: ApiClientOptions) {
    this.baseUrl = options.baseUrl.replace(/\/+$/, '');
    this.token = options.token;
    this.getToken = options.getToken;
    this.fetchImpl = options.fetchImpl ?? fetch;
  }

  setToken(token?: string): void {
    this.token = token;
  }

  login(payload: {
    email: string;
    password: string;
    device_name?: string;
    abilities?: string[];
  }): Promise<LoginResponse> {
    return this.request('POST', '/api/v1/login', payload);
  }

  me(): Promise<{ data: CurrentUser }> {
    return this.request('GET', '/api/v1/me');
  }

  logout(): Promise<void> {
    return this.request('POST', '/api/v1/logout');
  }

  listTokens(): Promise<{ data: TokenRecord[] }> {
    return this.request('GET', '/api/v1/tokens');
  }

  createToken(payload: { name: string; abilities?: string[] }): Promise<{
    token: string;
    token_type: string;
    abilities: string[];
    data: { id: number; name: string; created_at: string | null };
  }> {
    return this.request('POST', '/api/v1/tokens', payload);
  }

  deleteToken(tokenId: number): Promise<void> {
    return this.request('DELETE', `/api/v1/tokens/${tokenId}`);
  }

  adminSummary(): Promise<{ data: DashboardSummary }> {
    return this.request('GET', '/api/v1/admin/summary');
  }

  listLeads(filters: LeadFilters = {}): Promise<PaginatedResponse<Lead>> {
    return this.request('GET', `/api/v1/admin/leads${this.query(filters)}`);
  }

  getLead(id: number): Promise<{ data: Lead }> {
    return this.request('GET', `/api/v1/admin/leads/${id}`);
  }

  updateLead(id: number, payload: {
    status: Lead['status'];
    internal_notes?: string | null;
    payment_link?: string | null;
    quoted_value?: number | null;
  }): Promise<{ data: Partial<Lead> & { id: number } }> {
    return this.request('PATCH', `/api/v1/admin/leads/${id}`, payload);
  }

  deleteLead(id: number): Promise<void> {
    return this.request('DELETE', `/api/v1/admin/leads/${id}`);
  }

  listAdminPosts(filters: PostFilters = {}): Promise<PaginatedResponse<PostItem>> {
    return this.request('GET', `/api/v1/admin/posts${this.query(filters)}`);
  }

  getAdminPost(id: number): Promise<{ data: PostItem }> {
    return this.request('GET', `/api/v1/admin/posts/${id}`);
  }

  createPost(payload: Record<string, unknown>): Promise<{ data: PostItem }> {
    return this.request('POST', '/api/v1/admin/posts', payload);
  }

  updatePost(id: number, payload: Record<string, unknown>): Promise<{ data: PostItem }> {
    return this.request('PATCH', `/api/v1/admin/posts/${id}`, payload);
  }

  deletePost(id: number): Promise<void> {
    return this.request('DELETE', `/api/v1/admin/posts/${id}`);
  }

  listAdminProjects(filters: ProjectFilters = {}): Promise<PaginatedResponse<ProjectItem>> {
    return this.request('GET', `/api/v1/admin/projects${this.query(filters)}`);
  }

  getAdminProject(id: number): Promise<{ data: ProjectItem }> {
    return this.request('GET', `/api/v1/admin/projects/${id}`);
  }

  createProject(payload: Record<string, unknown>): Promise<{ data: ProjectItem }> {
    return this.request('POST', '/api/v1/admin/projects', payload);
  }

  updateProject(id: number, payload: Record<string, unknown>): Promise<{ data: ProjectItem }> {
    return this.request('PATCH', `/api/v1/admin/projects/${id}`, payload);
  }

  deleteProject(id: number): Promise<void> {
    return this.request('DELETE', `/api/v1/admin/projects/${id}`);
  }

  listAdminServices(filters: ServiceFilters = {}): Promise<PaginatedResponse<ServiceItem>> {
    return this.request('GET', `/api/v1/admin/services${this.query(filters)}`);
  }

  getAdminService(id: number): Promise<{ data: ServiceItem }> {
    return this.request('GET', `/api/v1/admin/services/${id}`);
  }

  createService(payload: Record<string, unknown>): Promise<{ data: ServiceItem }> {
    return this.request('POST', '/api/v1/admin/services', payload);
  }

  updateService(id: number, payload: Record<string, unknown>): Promise<{ data: ServiceItem }> {
    return this.request('PATCH', `/api/v1/admin/services/${id}`, payload);
  }

  deleteService(id: number): Promise<void> {
    return this.request('DELETE', `/api/v1/admin/services/${id}`);
  }

  listPublicPosts(filters: Record<string, string | number | boolean | undefined> = {}): Promise<PaginatedResponse<PostItem>> {
    return this.request('GET', `/api/v1/public/posts${this.query(filters)}`);
  }

  getPublicPost(slug: string): Promise<{ data: PostItem }> {
    return this.request('GET', `/api/v1/public/posts/${encodeURIComponent(slug)}`);
  }

  listPublicProjects(filters: Record<string, string | number | boolean | undefined> = {}): Promise<PaginatedResponse<ProjectItem>> {
    return this.request('GET', `/api/v1/public/projects${this.query(filters)}`);
  }

  getPublicProject(slug: string): Promise<{ data: ProjectItem }> {
    return this.request('GET', `/api/v1/public/projects/${encodeURIComponent(slug)}`);
  }

  listPublicServices(filters: Record<string, string | number | boolean | undefined> = {}): Promise<PaginatedResponse<ServiceItem>> {
    return this.request('GET', `/api/v1/public/services${this.query(filters)}`);
  }

  getPublicService(slug: string): Promise<{ data: ServiceItem }> {
    return this.request('GET', `/api/v1/public/services/${encodeURIComponent(slug)}`);
  }

  private async request<T>(method: HttpMethod, path: string, body?: unknown): Promise<T> {
    const headers: Record<string, string> = { Accept: 'application/json' };
    const token = this.getToken?.() ?? this.token;

    if (token) {
      headers.Authorization = `Bearer ${token}`;
    }

    const init: RequestInit = { method, headers };

    if (body !== undefined) {
      headers['Content-Type'] = 'application/json';
      init.body = JSON.stringify(body);
    }

    const response = await this.fetchImpl(`${this.baseUrl}${path}`, init);

    if (response.status === 204) {
      return undefined as T;
    }

    const text = await response.text();
    const payload = text ? (JSON.parse(text) as T & ApiErrorPayload) : null;

    if (!response.ok) {
      throw new EtuderapideApiError(
        response.status,
        payload?.message ?? `Request failed with status ${response.status}`,
        payload
      );
    }

    return payload as T;
  }

  private query(params: Record<string, string | number | boolean | undefined>): string {
    const searchParams = new URLSearchParams();

    Object.entries(params).forEach(([key, value]) => {
      if (value === undefined || value === null || value === '') {
        return;
      }

      searchParams.set(key, String(value));
    });

    const query = searchParams.toString();

    return query ? `?${query}` : '';
  }
}
