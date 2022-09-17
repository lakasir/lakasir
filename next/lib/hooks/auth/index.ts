import { useAuthApi } from "@/api/auth";
import { IFormLoginRequest, IFormRegisterRequest, ILogiResponse } from "lib/models/auth";
import { ErrorResponse, Response } from "@/models/response";
import { useRouter } from "next/router";
import { toast } from "react-hot-toast";
import { AxiosError } from "axios";

type ErrorHanlder = (error: ErrorResponse) => void;

export const useAuth = () => {
  const router = useRouter();
  const { loginAction, registerAction } = useAuthApi();

  const login = async (form: IFormLoginRequest, errors: ErrorHanlder) => {
    const toastId = toast.loading('Loging in...');
    try {
      const loginResponse = await loginAction(form);
      if (loginResponse instanceof AxiosError) {
        throw loginResponse;
      }
      const loginData = loginResponse as Response<ILogiResponse>;
      if (!loginData.success) {
        throw new Error(loginData.message);
      }
      toast.success('Login success');
      // save token auth in local storage
      localStorage.setItem('token', loginData.data.token);
      router.push("/menu");
    } catch (e) {
      if (e instanceof AxiosError) {
        const error = e as AxiosError<ErrorResponse>;
        errors(error.response?.data || {
          message: "",
          errors: {},
        });
        toast.error(error.response?.data.message || 'Login failed');
      }
    }
    toast.dismiss(toastId);
  }

  // create register function with parameter form IFormRegisterRequest and error handler
  const register = async (form: IFormRegisterRequest, errors: ErrorHanlder) => {
    const toastId = toast.loading('Registering...');
    try {
      const registerResponse = await registerAction(form);
      if (registerResponse instanceof AxiosError) {
        throw registerResponse;
      }
      const registerData = registerResponse as Response<ILogiResponse>;
      if (!registerData.success) {
        throw new Error(registerData.message);
      }
      toast.success('Register success and verify your email');
      router.push("/auth/login");
    } catch (e) {
      if (e instanceof AxiosError) {
        const error = e as AxiosError<ErrorResponse>;
        errors(error.response?.data || {
          message: "",
          errors: {},
        });
        toast.error(error.response?.data.message || 'Register failed');
      }
    }
    toast.dismiss(toastId);
  }

  return {
    login,
    register,
  }
}
