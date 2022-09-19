export type IMemberFormRequest = {
  email: string;
  name: string;
  code: string;
  address: string;
};

export type IMemberFormResponse = {
};

export type IMemberFormErrorReponse = {
  name: string[];
  code: string[];
  email: string[];
  address: string[];
};

export type IMemberResponse = {
  id: number;
  email: string;
  name: string;
  code: string;
  address: string;
  created_at: string;
  updated_at: string;
};

