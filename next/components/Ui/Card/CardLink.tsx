interface ICardLinkInterface {
  onClick?: (e: React.MouseEvent<HTMLAnchorElement>) => void;
  children: JSX.Element;
}
const CardLink = (props: ICardLinkInterface) => {
  return (
    <a className="block" onClick={props.onClick}>
      {props.children}
    </a>
  );
};

export default CardLink;
