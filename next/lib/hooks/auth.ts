import { useAuthApi } from "@/api/auth";
import { IForgotPasswordResponse, IFormForgotPasswordRequest, IFormLoginRequest, IFormRegisterRequest, IFormResetPasswordRequest, ILoginResponse } from "lib/models/auth";
import { ErrorResponse, Response } from "@/models/response";
import { useRouter } from "next/router";
import { toast } from "react-hot-toast";
import { AxiosError } from "axios";

type ErrorHanlder = (error: ErrorResponse) => void;

export const useAuth = () => {
  const router = useRouter();
  const { loginAction, registerAction, forgotPasswordAction, resetPasswordAction } = useAuthApi();

  const login = async (form: IFormLoginRequest, errors: ErrorHanlder) => {
    const toastId = toast.loading('Loging in...');
    try {
      const loginResponse = await loginAction(form);
      if (loginResponse instanceof AxiosError) {
        throw loginResponse;
      }
      const loginData = loginResponse as Response<ILoginResponse>;
      if (!loginData.success) {
        throw new Error(loginData.message);
      }
      toast.success('Login success');
      localStorage.setItem('token', loginData.data.token);
      router.push("/menu");
    } catch (e) {
      if (e instanceof AxiosError) {
        const error = e as AxiosError<ErrorResponse>;
        errors(error.response?.data || {
          message: "",
          errors: {},
        });
        toast.error('Login failed');
      }
    }
    toast.dismiss(toastId);
  }

  const register = async (form: IFormRegisterRequest, errors: ErrorHanlder) => {
    const toastId = toast.loading('Registering...');
    try {
      const registerResponse = await registerAction(form);
      if (registerResponse instanceof AxiosError) {
        throw registerResponse;
      }
      const registerData = registerResponse as Response<ILoginResponse>;
      if (!registerData.success) {
        throw new Error(registerData.message);
      }
      toast.success(registerData.message);
      router.push("/auth/login");
    } catch (e) {
      if (e instanceof AxiosError) {
        const error = e as AxiosError<ErrorResponse>;
        errors(error.response?.data || {
          message: "",
          errors: {},
        });
        toast.error('Register failed');
      }
    }
    toast.dismiss(toastId);
  }

  const forgotPassword = async (form: IFormForgotPasswordRequest, errors: ErrorHanlder) => {
    const toastId = toast.loading('Sending email...');
    try {
      const forgotPasswordResponse = await forgotPasswordAction(form);
      if (forgotPasswordResponse instanceof AxiosError) {
        throw forgotPasswordResponse;
      }
      const forgotPasswordData = forgotPasswordResponse as Response<IForgotPasswordResponse>;
      if (!forgotPasswordData.success) {
        throw new Error(forgotPasswordData.message);
      }
      toast.success(forgotPasswordData.message);
    } catch (e) {
      if (e instanceof AxiosError) {
        const error = e as AxiosError<ErrorResponse>;
        errors(error.response?.data || {
          message: "",
          errors: {},
        });
        toast.error('Send email failed');
      }
    }
    toast.dismiss(toastId);
  }

  const resetPassword = async (form: IFormResetPasswordRequest, errors: ErrorHanlder) => {
    const toastId = toast.loading('Resetting password...');
    try {
      const resetPasswordResponse = await resetPasswordAction(form);
      if (resetPasswordResponse instanceof AxiosError) {
        throw resetPasswordResponse;
      }
      const resetPasswordData = resetPasswordResponse as Response<IForgotPasswordResponse>;
      if (!resetPasswordData.success) {
        throw new Error(resetPasswordData.message);
      }
      toast.success(resetPasswordData.message);
      router.push("/auth/login");
    } catch (e) {
      if (e instanceof AxiosError) {
        const error = e as AxiosError<ErrorResponse>;
        errors(error.response?.data || {
          message: "",
          errors: {},
        });
        toast.error('Reset password failed');
      }
    }
    toast.dismiss(toastId);
  }



  return {
    login,
    register,
    forgotPassword,
    resetPassword,
  }
}
