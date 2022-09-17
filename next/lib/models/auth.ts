export type IFormLoginRequest = {
  email: string;
  password: string;
}

export type ILogiResponse = {
  token: string;
  id: number;
  name: string;
  email: string;
  email_verified_at: string;
  created_at: string;
  updated_at: string;
}

export type IFormRegisterRequest = {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export type IRegisterResponse = {
}
