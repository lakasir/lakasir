import { useMember } from "@/hooks/member";
import { IMemberResponse } from "@/models/member";
import { Response } from "@/models/response";
import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import { useRouter } from "next/router";
import { useEffect, useState } from "react";
import FormMember from "../form";

const EditMember: NextPage = () => {
  const router = useRouter();
  const { id } = router.query;
  const { getDetailMember } = useMember();
  const [member, setMember] = useState<IMemberResponse>();
  useEffect(() => {
    if (id) {
      getDetailMember(Number(id)).then((response) => {
        if (response) {
          const responseData = response as Response<IMemberResponse>;
          setMember(responseData.data);
        }
      });
    }
  }, [member, id]);

  if (!id) {
    return <>Loading...</>;
  }

  return (
    <Layout title="Edit Member" back={true}>
      <div className="py-3">
        <FormMember
          form={{
            name: member?.name,
            email: member?.email,
            code: member?.code,
            address: member?.address,
          }}
          id={Number(id)}
        />
      </div>
    </Layout>
  );
};

export default EditMember;
