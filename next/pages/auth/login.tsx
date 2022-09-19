import PasswordField from "@/components/Auth/PasswordField";
import { useAuth } from "@/hooks/auth";
import { IFormLoginRequest } from "@/models/auth";
import { ErrorResponse } from "@/models/response";
import { Button } from "@/ui/Buttons";
import { Checkbox, Form, Input } from "@/ui/Fields";
import { Layout } from "@/ui/Layout";
import type { NextPage } from "next";
import Link from "next/link";
import { FormEvent, useEffect, useState } from "react";

interface IErorrLoginResponse {
  email?: string;
  password?: string;
}

const Login: NextPage = () => {
  const { login } = useAuth();
  const [errors, setErrors] = useState<IErorrLoginResponse>({});
  const loginSubmit = (_: FormEvent, values: IFormLoginRequest) => {
    login(values, (error: ErrorResponse) => {
      setErrors({
        email: error.errors.email ? error.errors.email[0] : "",
        password: error.errors.password ? error.errors.password[0] : "",
      });
    });
  };
  useEffect(() => {}, [errors]);
  return (
    <Layout nosavearea>
      <div className="grid gap-16">
        <p className="flex justify-center items-end h-56 text-[32px] font-semibold">
          Sign In <span className="ml-2 text-lakasir-primary"> LAKASIR</span>
        </p>
        <Form
          className="space-y-5"
          initialValue={{
            email: "",
            password: "",
            remember: true,
          }}
          onSubmit={loginSubmit}
        >
          {() => (
            <>
              <Input
                name={"email"}
                type={"text"}
                error={errors.email}
                label={
                  <>
                    Username or Email<span className="text-red-500">*</span>
                  </>
                }
              />
              <PasswordField
                error={errors.password}
                label={
                  <>
                    Password<span className="text-red-500">*</span>
                  </>
                }
                name={"password"}
              />
              <Checkbox name={"remember"} label={"Remember Me"} />
              <Button className="w-full py-4">Sign in</Button>
            </>
          )}
        </Form>
        <div>
          <Link href="/auth/forgot-password">
            <a className="text-lakasir-primary font-medium flex justify-center text-center mb-7">
              Forgot the password?
            </a>
          </Link>
          <p className="font-medium text-center">
            Dont have an account?{" "}
            <Link href="/auth/register">
              <a className="text-lakasir-primary">Sign up</a>
            </Link>
          </p>
        </div>
      </div>
    </Layout>
  );
};

export default Login;
