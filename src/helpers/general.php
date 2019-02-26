function editing()
{
    $request = Request::instance();

    if (($request->routeInfo()->method() == 'onEdit' and $request->has('id')) or ($request->has('editing') or $request->attr('editing'))) {
        return true;
    }
    return false;
}