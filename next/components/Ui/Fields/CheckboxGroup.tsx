interface ICheckboxOption {
  label: string;
  value: string | number;
  name: string;
}
export interface ICheckboxInterface {
  label: string | JSX.Element;
  options: ICheckboxOption[];
  prekey?: string;
  freetext?: string;
}

const CheckboxGroup = (props: ICheckboxInterface) => {
  // WIP: create CheckboxGroup <16-08-22, shenazien8> //
  return (
    <>
      <label>{props.label}</label>
      {props.options.map((option, index) => (
        <Checkbox name={option.name} label={option.label} key={index}/>
      ))}
    </>
  );
};

export { CheckboxGroup };
