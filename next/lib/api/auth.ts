import { IForgotPasswordResponse, IFormForgotPasswordRequest, IFormLoginRequest, IFormResetPasswordRequest, ILoginResponse, IRegisterResponse, IResetPasswordResponse } from "@/models/auth";
import { Response } from "@/models/response";
import axios from "@/utils/axios";

export const useAuthApi = () => {
  const loginAction = async (form: IFormLoginRequest): Promise<Response<ILoginResponse> | Error>=> {
    try {
      return axios.get('/sanctum/csrf-cookie').then(async () => {
        const data = await axios.post<Response<ILoginResponse>>("/api/auth/login", form);
        if (data.status !== 200) {
          throw new Error(data.statusText);
        }
        return data.data;
      })
      .catch((error) => {
        throw error;
      });
    } catch (error) {
      throw error;
    }
  };

  const registerAction = async (form: IFormLoginRequest): Promise<Response<IRegisterResponse> | Error>=> {
    try {
      return axios.get('/sanctum/csrf-cookie').then(async () => {
        const data = await axios.post<Response<IRegisterResponse>>("/api/auth/register", form);
        if (![200, 204].includes(data.status)) {
          throw new Error(data.statusText);
        }
        return data.data;
      })
      .catch((error) => {
        throw error;
      });
    } catch (error) {
      throw error;
    }
  }

  const forgotPasswordAction = async (form: IFormForgotPasswordRequest): Promise<Response<IForgotPasswordResponse> | Error>=> {
    try {
      return axios.get('/sanctum/csrf-cookie').then(async () => {
        const data = await axios.post<Response<IForgotPasswordResponse>>("/api/auth/forgot-password", form);
        if (![200, 204].includes(data.status)) {
          throw new Error(data.statusText);
        }
        return data.data;
      })
      .catch((error) => {
        throw error;
      });
    } catch (error) {
      throw error;
    }
  }

  const resetPasswordAction = async (form: IFormResetPasswordRequest): Promise<Response<IResetPasswordResponse> | Error>=> {
    try {
      return axios.get('/sanctum/csrf-cookie').then(async () => {
        const data = await axios.post<Response<IResetPasswordResponse>>("/api/auth/reset-password", form);
        if (![200, 204].includes(data.status)) {
          throw new Error(data.statusText);
        }
        return data.data;
      })
      .catch((error) => {
        throw error;
      });
    } catch (error) {
      throw error;
    }
  }

  return {
    loginAction,
    registerAction,
    forgotPasswordAction,
    resetPasswordAction
  };
};
