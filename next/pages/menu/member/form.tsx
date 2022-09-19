import { useMember } from "@/hooks/member";
import { IMemberFormErrorReponse, IMemberFormRequest } from "@/models/member";
import { Button } from "@/ui/Buttons";
import { Form, Input } from "@/ui/Fields";
import { FormEvent, useEffect, useRef, useState } from "react";

interface IFormMemberInterface {
  form?: MemberData;
  id?: number;
}

interface MemberData {
  name?: string;
  code?: string;
  email?: string;
  address?: string;
}

interface IErrorsMemberResponse {
  name?: string;
  code?: string;
  email?: string;
  address?: string;
}

const FormMember = (props: IFormMemberInterface) => {
  const { createMember, updateMember } = useMember()
  const [errors, setErrors] = useState<IErrorsMemberResponse>();
  const updateOrCreate = async (_: FormEvent, values: IMemberFormRequest) => {
    if (props.id != undefined) {
      await updateMember(values, props.id, (errors) => {
          setErrors({
            name: errors.name ? errors.name[0] : "",
            code: errors.code ? errors.code[0] : "",
            email: errors.email ? errors.email[0] : "",
            address: errors.address ? errors.address[0] : "",
          });
      });
    } else {
      await createMember(values, (errors) => {
        setErrors({
          name: errors.name ? errors.name[0] : "",
          code: errors.code ? errors.code[0] : "",
          email: errors.email ? errors.email[0] : "",
          address: errors.address ? errors.address[0] : "",
        });
      });
    }
  };
  useEffect(() => {}, [errors]);
  return (
    <Form
      className="space-y-8"
      initialValue={{
        ...props.form,
      }}
      onSubmit={updateOrCreate}
    >
      {() => (
        <>
          <Input
            name={"name"}
            type={"text"}
            error={errors?.name}
            label={
              <>
                Name<span className="text-red-500">*</span>
              </>
            }
          />
          <Input
            name={"email"}
            error={errors?.email}
            type={"text"}
            label={
              <>
                Email<span className="text-red-500">*</span>
              </>
            }
          />
          <Input
            name={"code"}
            error={errors?.code}
            type={"text"}
            placeholder={"Leave it blank to use code generator from app"}
            label={
              <>
                Code<span className="text-red-500">*</span>
              </>
            }
          />
          <Input
            error={errors?.address}
            name={"address"}
            type={"textarea"}
            label={
              <>
                Address<span className="text-red-500">*</span>
              </>
            }
          />
          <Button className="w-full py-4">Save</Button>
        </>
      )}
    </Form>
  );
};

export default FormMember;
