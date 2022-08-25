import { NextPage } from "next";
import Layout from "../../../../components/Ui/Layout";
import FormMember from "../form";

const EditMember: NextPage = () => {
  return (
    <Layout title="Edit Member" back={true}>
      <div className="py-3">
        <FormMember />
      </div>
    </Layout>
  );
};

export default EditMember;
