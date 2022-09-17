export type Response<Type> = {
  success: boolean;
  message: string;
  data: Type
}

export type ErrorResponse = {
  message: string;
  errors: {
    [key: string]: string[]
  }
}

