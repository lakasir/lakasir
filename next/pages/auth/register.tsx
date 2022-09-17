import PasswordField from "@/components/Auth/PasswordField";
import { useAuth } from "@/hooks/auth";
import { IFormRegisterRequest } from "@/models/auth";
import { ErrorResponse } from "@/models/response";
import { Button } from "@/ui/Buttons";
import { Checkbox, Form, Input } from "@/ui/Fields";
import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import Link from "next/link";
import { FormEvent, useState } from "react";


interface IErorrRegisterResponse {
  name?: string;
  email?: string;
  password?: string;
  password_confirmation?: string;
}

const Register: NextPage = () => {
  const { register } = useAuth();
  const [errors, setErrors] = useState<IErorrRegisterResponse>({});
  const submitRegister = async (_: FormEvent, values: IFormRegisterRequest) => {
    register(values, (error: ErrorResponse) => {
      setErrors({
        name: error.errors.name ? error.errors.name[0] : "",
        email: error.errors.email ? error.errors.email[0] : "",
        password: error.errors.password ? error.errors.password[0] : "",
        password_confirmation: error.errors.password_confirmation ? error.errors.password_confirmation[0] : "",
      });
    })
  }
  return (
    <Layout nosavearea>
      <div className="grid gap-12">
        <p className="flex justify-center items-end h-40 text-[32px] font-semibold">
          Sign Up <span className="ml-2 text-lakasir-primary"> LAKASIR</span>
        </p>
        <Form
          className="space-y-5"
          initialValue={{
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
          }}
          onSubmit={submitRegister}
        >
          {() => (
            <>
              <Input
                error={errors.name}
                name={"name"}
                type={"text"}
                label={
                  <>
                    Full Name<span className="text-red-500">*</span>
                  </>
                }
              />
              <Input
                error={errors.email}
                name={"email"}
                type={"text"}
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
              <PasswordField
                error={errors.password_confirmation}
                label={
                  <>
                    Confirm Password<span className="text-red-500">*</span>
                  </>
                }
                name={"password_confirmation"}
              />
              <Checkbox
                name={"remember_me"}
                label={
                  <span className="font-medium">
                    By creating an account, you agree to our <br />{" "}
                    <Link href={"/terms"}>
                      <a className="text-lakasir-primary">
                        Terms and Conditions
                      </a>
                    </Link>
                  </span>
                }
              />
              <Button className="w-full py-4">Register</Button>
            </>
          )}
        </Form>
        <div className="mb-20">
          <p className="font-medium text-center">
            You have an account?{" "}
            <Link href="/auth/login">
              <a className="text-lakasir-primary">Sign In</a>
            </Link>
          </p>
        </div>
      </div>
    </Layout>
  );
};

export default Register;
